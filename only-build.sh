#!/bin/bash

# Zeroy Theme 本地仅构建脚本（不上传）

set -e

# 颜色输出
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 开始仅构建 Zeroy 主题...${NC}"

# 获取主题版本
VERSION=$(grep "Version:" style.css | head -1 | sed 's/Version: //' | tr -d ' ')
if [ -z "$VERSION" ]; then
  echo -e "${RED}❌ 未能从 style.css 获取版本号${NC}"
  exit 1
fi
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
          --exclude='only-build.sh' \
          --exclude='.env' \
          --exclude='.claude' \
          --exclude='.wrangler' \
          --exclude='*.zip' \
          . "$TEMP_DIR/zeroy/"

# 创建 ZIP 文件（命名为 zeroy-theme-x.y.z.zip）
echo -e "${YELLOW}📦 创建 ZIP 文件...${NC}"
cd "$TEMP_DIR"
zip -r "$OLDPWD/zeroy-theme-$VERSION.zip" zeroy/ >/dev/null
cd "$OLDPWD"

# 验证 ZIP 文件
if [ -f "zeroy-theme-$VERSION.zip" ]; then
  echo -e "${GREEN}✅ ZIP 文件创建成功:${NC}"
  ls -la "zeroy-theme-$VERSION.zip"
else
  echo -e "${RED}❌ ZIP 文件创建失败${NC}"
  exit 1
fi

# 清理临时目录
rm -rf "$TEMP_DIR"

echo -e "${GREEN}✨ 仅构建完成! 输出: zeroy-theme-$VERSION.zip${NC}"

