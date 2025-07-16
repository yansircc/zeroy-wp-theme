#!/bin/bash

# Zeroy Theme 构建和上传脚本

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

# 上传到 R2 (如果配置了环境变量)
if [ -n "$CLOUDFLARE_API_TOKEN" ] && [ -n "$CLOUDFLARE_ACCOUNT_ID" ]; then
    echo -e "${YELLOW}☁️  上传到 Cloudflare R2...${NC}"
    
    # 检查是否安装了 wrangler
    if ! command -v wrangler &> /dev/null; then
        echo -e "${YELLOW}📥 安装 wrangler...${NC}"
        npm install -g wrangler
    fi
    
    # 上传到 R2
    wrangler r2 object put "zeroy/zeroy-$VERSION.zip" \
        --file "zeroy-$VERSION.zip" \
        --content-type "application/zip"
    
    # 上传 latest 版本
    wrangler r2 object put "zeroy/zeroy-latest.zip" \
        --file "zeroy-$VERSION.zip" \
        --content-type "application/zip"
    
    echo -e "${GREEN}✅ 上传到 R2 成功!${NC}"
else
    echo -e "${YELLOW}⚠️  未配置 Cloudflare 环境变量，跳过上传到 R2${NC}"
    echo -e "${YELLOW}   请设置 CLOUDFLARE_API_TOKEN 和 CLOUDFLARE_ACCOUNT_ID${NC}"
fi

echo -e "${GREEN}🎉 构建完成!${NC}"
echo -e "${GREEN}   ZIP 文件: zeroy-$VERSION.zip${NC}"

# 清理临时目录
rm -rf "$TEMP_DIR"

echo -e "${GREEN}✨ 所有任务完成!${NC}"