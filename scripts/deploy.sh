#!/bin/bash
CSDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
WEBDOMAIN=mydomain
SERVERDIR=/home/angelhack/$WEBDOMAIN
FWSLASH=/
SERVERUSR=angelhack

echo "Current script dir: $CSDIR"

date_time=$(date +%Y%m%d_%H%M)
echo "Current script date: $date_time"

archive=$WEBDOMAIN$date_time
archive_name=$archive.zip
sym_link_target="../$archive/symfony/web"

echo "Run git archive: $archive_name"
git archive --format zip --output ./$archive_name master
echo "SCP archive $WEBDOMAIN$date_time.zip to server"
scp $archive_name $SERVERUSR@$WEBDOMAIN:$SERVERDIR
echo "Delete $archive_name "
rm $archive_name


echo "Running remote setup with symlink $sym_link_target"
#ssh nnnnnnnn@oooooo.dreamhostps.com "unzip \"$SERVERDIR$archive_name\" -d \"$SERVERDIR$archive\" > /dev/null;ln -s  \"$sym_link_target\" \"$SERVERDIR\"reviewme_tmp && mv -Tf \"$SERVERDIR\"reviewme_tmp \"$SERVERDIR\"reviewme;  rm \"$SERVERDIR$archive_name\" "
ssh $SERVERUSR@$WEBDOMAIN "unzip \"$SERVERDIR$archive_name\" -d \"$SERVERDIR$archive\" > /dev/null;cp -rf \"$SERVERDIR$archive$FWSLASH\"reviewme \"$SERVERDIR\";  rm \"$SERVERDIR$archive_name\" "






