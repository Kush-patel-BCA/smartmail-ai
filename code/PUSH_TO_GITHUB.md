# How to Push to GitHub - Step by Step

## ✅ Remote Repository Added Successfully!

The remote repository has been added. Now you need to authenticate to push.

## 🔐 Create Personal Access Token

1. **Go to GitHub Settings:**
   - Visit: https://github.com/settings/tokens
   - Or: GitHub → Your Profile → Settings → Developer settings → Personal access tokens → Tokens (classic)

2. **Generate New Token:**
   - Click "Generate new token" → "Generate new token (classic)"
   - Give it a name: `smartmail-ai-push`
   - Select expiration: Choose your preference (90 days, 1 year, or no expiration)
   - **Select scopes:** Check `repo` (this gives full control of private repositories)
   - Click "Generate token"

3. **Copy the Token:**
   - ⚠️ **IMPORTANT:** Copy the token immediately - you won't see it again!
   - It will look like: `ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

## 🚀 Push to GitHub

After creating the token, run this command:

```bash
git push -u origin main
```

When prompted:
- **Username:** Enter `Kush-patel-BCA`
- **Password:** Paste your Personal Access Token (not your GitHub password!)

## 🔄 Alternative: Use Token in URL (One-time)

You can also use the token directly in the URL (for one-time push):

```bash
git push https://YOUR_TOKEN@github.com/Kush-patel-BCA/smartmail-ai.git main
```

Replace `YOUR_TOKEN` with your actual token.

## 📝 Your Repository

After pushing, your project will be live at:
**https://github.com/Kush-patel-BCA/smartmail-ai**

---

**Note:** If you prefer SSH authentication instead, let me know and I can help set that up!

