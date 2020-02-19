# Save latest Git commit message
MESSAGE=$(git log -1 --pretty=%B)

# Add it as a secret variable on CI environment
git remote set-url origin $REMOTE_REPOSITORY
git config user.email $DEPLOY_GIT_EMAIL
git config user.name $DEPLOY_GIT_USERNAME

if git ls-remote $REMOTE_REPOSITORY | grep -sw "deploy" 2>&1>/dev/null; then
    git checkout . && git checkout deploy;
else
    git checkout --orphan deploy;
fi

# Remove all files except .git, node_modules and vendor folder
find . -path ./.git -prune -o \( \! -path ./dist \) -prune -o \( \! -path ./vendor \) -exec rm -rf {} \; 2> /dev/null
# Add master branch files
git archive master | tar x -C .

# Generate i18n string for the front-end
composer install -n --prefer-dist
php artisan vue-i18n:generate

# Compile front-end stuff
npm install
npm run ci

# Those files are ignored on master branch, force add it
git add -f public/css
git add -f public/js

# Push to the deploy branch
git add .
git commit -am "[${CI_COMMIT_SHA:0:8}]: $MESSAGE"
git push -u origin deploy
