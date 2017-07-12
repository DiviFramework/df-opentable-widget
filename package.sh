version=$1
plugin_basename=$(basename $(pwd))

#clean up
rm -rf /tmp/$plugin_basename;
rm /tmp/$plugin_basename-$version.zip;


cd ..;
cp -r $plugin_basename /tmp;

cd -;
cd /tmp;

zip -r9 $plugin_basename-$version.zip $plugin_basename -x *.git* -x *.sh -x *.json -x *.lock -x Gruntfile.js;
rm -rf /tmp/$plugin_basename;

#upload to s3.
rclone mkdir df-s3:diviframework/$plugin_basename;
rclone copy /tmp/$plugin_basename-$version.zip df-s3:diviframework/$plugin_basename;
echo "https://s3-ap-southeast-2.amazonaws.com/diviframework/$plugin_basename/$plugin_basename-$version.zip"