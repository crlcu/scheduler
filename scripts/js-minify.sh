# before running this file make sure you have installed node

# require : less
# install : npm install -g less

# require : minifyjs
# install : npm install -g minifyjs

# minify app.js
echo "Minifying 'app.js'"
minifyjs -m -i public/js/app.js -o public/js/app.min.js

echo "DONE";
