#!/bin/bash

# Zeroy Theme 构建和上传脚本 (使用 API)

set -e

# 颜色输出
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 加载 .env 文件
if [ -f .env ]; then
    echo -e "${YELLOW}📝 加载环境变量...${NC}"
    export $(cat .env | grep -v '^#' | xargs)
fi

echo -e "${GREEN}🚀 开始构建 Zeroy 主题...${NC}"

# 获取主题版本
VERSION=$(grep "Version:" style.css | head -1 | sed 's/Version: //' | tr -d ' ')
echo -e "${YELLOW}📦 主题版本: $VERSION${NC}"

# 创建临时目录
echo -e "${YELLOW}📁 创建临时目录...${NC}"
TEMP_DIR="/tmp/zeroy-build-$$"
rm -rf "$TEMP_DIR"
mkdir -p "$TEMP_DIR"

# 复制主题文件，排除不需要的文件
echo -e "${YELLOW}📋 复制主题文件...${NC}"
rsync -av --exclude='.git' \
          --exclude='.github' \
          --exclude='node_modules' \
          --exclude='dist' \
          --exclude='.gitignore' \
          --exclude='.gitattributes' \
          --exclude='package.json' \
          --exclude='package-lock.json' \
          --exclude='README.md' \
          --exclude='build-and-upload.sh' \
          --exclude='.env' \
          --exclude='.claude' \
          --exclude='.wrangler' \
          --exclude='*.zip' \
          . "$TEMP_DIR/zeroy/"

# 创建 ZIP 文件
echo -e "${YELLOW}📦 创建 ZIP 文件...${NC}"
cd "$TEMP_DIR"
zip -r "$OLDPWD/zeroy-$VERSION.zip" zeroy/
cd "$OLDPWD"

# 验证 ZIP 文件
echo -e "${GREEN}✅ ZIP 文件创建成功:${NC}"
ls -la "zeroy-$VERSION.zip"

# 通过 API 上传
API_URL="${API_URL:-https://zeroy.yansir.workers.dev}"
echo -e "${YELLOW}☁️  上传到 API: $API_URL${NC}"

# 使用 curl 上传文件
TEMP_RESPONSE="/tmp/upload-response-$$"
HTTP_CODE=$(curl -X POST \
  -F "file=@zeroy-$VERSION.zip" \
  -F "theme=zeroy" \
  -F "version=$VERSION" \
  "$API_URL/api/wp-updates/themes/upload" \
  -s -w "%{http_code}" \
  -o "$TEMP_RESPONSE")

BODY=$(cat "$TEMP_RESPONSE")
rm -f "$TEMP_RESPONSE"

if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ 上传成功!${NC}"
    echo "$BODY" | jq '.' 2>/dev/null || echo "$BODY"
else
    echo -e "${RED}❌ 上传失败 (HTTP $HTTP_CODE)${NC}"
    echo "$BODY" | jq '.' 2>/dev/null || echo "$BODY"
    exit 1
fi

echo -e "${GREEN}🎉 构建和上传完成!${NC}"
echo -e "${GREEN}   ZIP 文件: zeroy-$VERSION.zip${NC}"

# 清理临时目录
rm -rf "$TEMP_DIR"

echo -e "${GREEN}✨ 所有任务完成!${NC}"