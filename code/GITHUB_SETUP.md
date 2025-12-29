# GitHub Setup Instructions

## Step 1: Create Repository on GitHub

1. Go to https://github.com/Kush-patel-BCA (or https://github.com/KushPatel)
2. Click the "+" icon in the top right corner
3. Select "New repository"
4. Repository name: `smartmail-ai` (or any name you prefer)
5. Description: "SmartMail AI - Intelligent Email Management System with AI-powered email generation"
6. Choose **Public** or **Private**
7. **DO NOT** initialize with README, .gitignore, or license (we already have these)
8. Click "Create repository"

## Step 2: Add Remote and Push

After creating the repository, run these commands in your terminal:

```bash
# Add remote repository (replace with your actual GitHub username)
git remote add origin https://github.com/Kush-patel-BCA/smartmail-ai.git

# Or if you prefer SSH:
# git remote add origin git@github.com:Kush-patel-BCA/smartmail-ai.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Alternative: If repository already exists

If you already created the repository on GitHub, use:

```bash
git remote add origin https://github.com/Kush-patel-BCA/smartmail-ai.git
git branch -M main
git push -u origin main
```

## Note

- If you're asked for credentials, use a Personal Access Token (not password)
- To create a token: GitHub → Settings → Developer settings → Personal access tokens → Generate new token
- Select scopes: `repo` (full control of private repositories)

## Your Repository URL

After pushing, your project will be available at:
- https://github.com/Kush-patel-BCA/smartmail-ai

