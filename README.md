# Zeroy WordPress Theme

极简 WordPress 主题，集成 Tailwind CSS v4，零干扰设计，支持自动更新。

## 功能特点

- ✨ 极简设计理念
- 🎨 集成 Tailwind CSS v4
- 🔄 自动更新功能
- 📱 响应式设计
- ⚡ 性能优化

## 安装方法

### 方法 1: 手动安装
1. 下载最新版本的 ZIP 文件
2. 在 WordPress 后台上传主题
3. 激活主题

### 方法 2: 自动更新
如果您的主题已经安装，WordPress 后台会自动显示更新提示。

## 开发

### 本地开发
1. 克隆此仓库到 WordPress 主题目录
2. 修改主题文件
3. 测试功能

### 构建和发布
```bash
# 本地构建
./build-and-upload.sh

# 或者推送到 GitHub 自动构建
git push origin main
```

## CI/CD 配置

### GitHub Secrets 配置
在 GitHub 仓库中设置以下 Secrets：

1. **CLOUDFLARE_API_TOKEN**: Cloudflare API 令牌
2. **CLOUDFLARE_ACCOUNT_ID**: Cloudflare 账户 ID

### 获取 Cloudflare 凭据

#### 1. 获取 API Token
1. 访问 [Cloudflare Dashboard](https://dash.cloudflare.com/profile/api-tokens)
2. 点击 "Create Token"
3. 选择 "Custom token"
4. 权限设置：
   - Account: `Cloudflare Workers:Edit`
   - Zone Resources: `Include All zones`
   - Account Resources: `Include All accounts`

#### 2. 获取 Account ID
1. 访问 [Cloudflare Dashboard](https://dash.cloudflare.com)
2. 选择您的账户
3. 在右侧边栏找到 "Account ID"

### 自动构建触发条件

- **推送到 main 分支**: 自动构建并上传到 R2
- **创建 tag (v*)**: 自动构建、上传到 R2 并创建 GitHub Release
- **手动触发**: 在 GitHub Actions 中手动运行工作流

### 版本管理
主题版本在 `style.css` 中定义：
```css
/*
Theme Name: Zeroy
Version: 1.0.3
*/
```

更新版本号后推送到 GitHub 即可自动构建新版本。

## 文件结构

```
zeroy/
├── .github/workflows/
│   └── release.yml          # GitHub Actions 工作流
├── inc/
│   └── theme-updater.php    # 自动更新功能
├── functions.php            # 主题功能
├── style.css               # 主题样式和信息
├── index.php               # 主题入口
├── build-and-upload.sh     # 本地构建脚本
└── README.md              # 说明文档
```

## API 端点

主题更新 API 由 `https://www.zeroy.app` 提供：

- `GET /api/theme-updates/check` - 检查更新
- `GET /api/theme-updates/info/{theme}` - 获取主题信息
- `GET /api/theme-updates/changelog/{theme}` - 获取更新日志
- `GET /api/theme-updates/download/{theme}` - 下载主题
- `POST /api/theme-updates/cache/clear` - 清除缓存

## 技术栈

- **前端**: HTML5, CSS3, JavaScript
- **样式**: Tailwind CSS v4
- **后端**: PHP 8.0+
- **存储**: Cloudflare R2
- **CDN**: Cloudflare Workers
- **CI/CD**: GitHub Actions

## 支持

- 网站: https://zeroy.dev
- 邮箱: support@zeroy.dev
- 文档: https://docs.zeroy.dev

## 许可证

GPL v2 or later

---

🚀 自动构建和发布