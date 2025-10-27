# Complete Step-by-Step Deployment Guide for SeQueueR

## Prerequisites
- Node.js 18+ installed on your computer
- Git installed
- Vercel account (free tier works fine)
- GitHub account (for code repository)

## Step 1: Prepare Your Project

### 1.1 Create a GitHub Repository
1. Go to [GitHub.com](https://github.com) and sign in
2. Click "New repository"
3. Name it `qms-nextjs` or `sequer-vercel`
4. Make it public (required for free Vercel deployment)
5. Click "Create repository"

### 1.2 Upload Your Code to GitHub
1. Open terminal/command prompt in your project folder
2. Run these commands:
```bash
git init
git add .
git commit -m "Initial commit - QMS Next.js project"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git push -u origin main
```

## Step 2: Set Up Vercel Account

### 2.1 Create Vercel Account
1. Go to [vercel.com](https://vercel.com)
2. Click "Sign Up"
3. Choose "Continue with GitHub" (recommended)
4. Authorize Vercel to access your GitHub

### 2.2 Install Vercel CLI (Optional but Recommended)
```bash
npm install -g vercel
```

## Step 3: Deploy to Vercel

### 3.1 Deploy via Vercel Dashboard (Easiest Method)
1. Go to [vercel.com/dashboard](https://vercel.com/dashboard)
2. Click "New Project"
3. Import your GitHub repository
4. Click "Import" next to your repository
5. Vercel will automatically detect it's a Next.js project
6. Click "Deploy" (don't change any settings yet)

### 3.2 Deploy via CLI (Alternative Method)
1. Open terminal in your project folder
2. Run: `vercel login`
3. Run: `vercel`
4. Follow the prompts:
   - Set up and deploy? `Y`
   - Which scope? Choose your account
   - Link to existing project? `N`
   - Project name? `qms-nextjs` (or your preferred name)
   - Directory? `./` (current directory)
   - Override settings? `N`

## Step 4: Set Up Vercel Postgres Database

### 4.1 Add Postgres Database
1. Go to your Vercel dashboard
2. Click on your deployed project
3. Go to "Storage" tab
4. Click "Create Database"
5. Choose "Postgres"
6. Name it `qms-database`
7. Click "Create"

### 4.2 Get Database Connection Strings
1. After database is created, click on it
2. Go to "Settings" tab
3. Copy these values (you'll need them in Step 5):
   - `POSTGRES_URL`
   - `POSTGRES_PRISMA_URL`
   - `POSTGRES_URL_NON_POOLING`
   - `POSTGRES_USER`
   - `POSTGRES_HOST`
   - `POSTGRES_PASSWORD`
   - `POSTGRES_DATABASE`

## Step 5: Configure Environment Variables

### 5.1 Add Environment Variables in Vercel
1. Go to your project dashboard
2. Click "Settings" tab
3. Click "Environment Variables"
4. Add each variable one by one:

```
POSTGRES_URL = (paste from Step 4.2)
POSTGRES_PRISMA_URL = (paste from Step 4.2)
POSTGRES_URL_NON_POOLING = (paste from Step 4.2)
POSTGRES_USER = (paste from Step 4.2)
POSTGRES_HOST = (paste from Step 4.2)
POSTGRES_PASSWORD = (paste from Step 4.2)
POSTGRES_DATABASE = (paste from Step 4.2)
JWT_SECRET = your-super-secret-jwt-key-make-it-long-and-random-12345
```

### 5.2 Generate JWT Secret
For `JWT_SECRET`, use a long random string. You can generate one:
```bash
node -e "console.log(require('crypto').randomBytes(64).toString('hex'))"
```

## Step 6: Set Up Database Schema

### 6.1 Connect to Your Database
1. Go to your Vercel dashboard
2. Click on your project
3. Go to "Storage" tab
4. Click on your Postgres database
5. Click "Connect" tab
6. Copy the connection string

### 6.2 Run Database Schema
**Option A: Using Vercel Dashboard**
1. Go to your database dashboard
2. Click "Query" tab
3. Copy and paste the entire content from `database/postgres-schema.sql`
4. Click "Run"

**Option B: Using psql (if you have it installed)**
```bash
psql "your_postgres_connection_string"
```
Then paste the SQL commands from `database/postgres-schema.sql`

**Option C: Using a database client**
- Use tools like pgAdmin, DBeaver, or TablePlus
- Connect using the connection string from Step 6.1
- Run the SQL commands from `database/postgres-schema.sql`

## Step 7: Redeploy with Environment Variables

### 7.1 Trigger New Deployment
1. Go to your Vercel project dashboard
2. Click "Deployments" tab
3. Click the three dots next to your latest deployment
4. Click "Redeploy"
5. Or push a new commit to trigger automatic deployment:
```bash
git add .
git commit -m "Add environment variables"
git push
```

## Step 8: Test Your Deployment

### 8.1 Test Student Interface
1. Go to `https://your-project-name.vercel.app/student`
2. Try the queue request process
3. Verify it works end-to-end

### 8.2 Test Admin Login
1. Go to `https://your-project-name.vercel.app/personnel/signin`
2. Login with:
   - Username: `admin`
   - Password: `admin123`
3. Verify you're redirected to admin dashboard

### 8.3 Test Working Personnel Interface
1. Create a staff account through admin panel
2. Login as staff
3. Verify you're redirected to working queue interface

## Step 9: Configure Custom Domain (Optional)

### 9.1 Add Custom Domain
1. Go to your project settings
2. Click "Domains" tab
3. Add your domain (e.g., `qms.yourdomain.com`)
4. Follow DNS configuration instructions

### 9.2 Update DNS Records
1. Go to your domain registrar
2. Add CNAME record pointing to your Vercel domain
3. Wait for DNS propagation (up to 24 hours)

## Step 10: Monitor and Maintain

### 10.1 Set Up Monitoring
1. Enable Vercel Analytics (free)
2. Monitor deployment logs
3. Set up error tracking

### 10.2 Regular Maintenance
1. Monitor database usage
2. Check for failed deployments
3. Update dependencies regularly

## Troubleshooting Common Issues

### Issue 1: Database Connection Error
**Solution:**
- Verify all environment variables are set correctly
- Check database is running in Vercel dashboard
- Ensure connection strings are complete

### Issue 2: Authentication Not Working
**Solution:**
- Verify JWT_SECRET is set
- Check if database schema was run correctly
- Ensure admin user was created

### Issue 3: Build Failures
**Solution:**
- Check Vercel build logs
- Ensure all dependencies are in package.json
- Verify TypeScript errors are fixed

### Issue 4: Environment Variables Not Loading
**Solution:**
- Redeploy after adding environment variables
- Check variable names match exactly
- Ensure no extra spaces in values

## Quick Commands Reference

```bash
# Install Vercel CLI
npm install -g vercel

# Login to Vercel
vercel login

# Deploy to Vercel
vercel

# Deploy to production
vercel --prod

# Check deployment status
vercel ls

# View logs
vercel logs
```

## Success Checklist

- [ ] GitHub repository created and code uploaded
- [ ] Vercel account created and project deployed
- [ ] Postgres database created in Vercel
- [ ] Environment variables configured
- [ ] Database schema executed
- [ ] Project redeployed with new settings
- [ ] Student interface tested
- [ ] Admin login tested
- [ ] Staff interface tested
- [ ] Custom domain configured (optional)

## Support Resources

- [Vercel Documentation](https://vercel.com/docs)
- [Next.js Documentation](https://nextjs.org/docs)
- [Vercel Postgres Documentation](https://vercel.com/docs/storage/vercel-postgres)

Your QMS system should now be live and accessible at `https://your-project-name.vercel.app`! 🎉
