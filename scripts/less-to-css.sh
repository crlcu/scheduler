# before running this file make sure you have installed node

# require : less
# install : npm install -g less

# require : uglifycss
# install : npm install -g uglifycss

echo "Running less-to-css script"

# compile and uglify style.less
echo "Compiling 'less/style.less' to 'style.css'"
lessc public/css/less/style.less public/css/style.css

echo "Uglifying 'style.css' to 'style.min.css'";
uglifycss public/css/style.css > public/css/style.min.css

echo "Removing 'style.css'";
rm -rf public/css/style.css

echo "DONE";
