#!/usr/bin/env bash


mydir=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
tmpdir="$mydir/.tmp"
#rm -rf "$tmpdir"
#mkdir "$tmpdir"
#
#git clone https://github.com/stephenhutchings/typicons.font "$tmpdir/typicons"
#git clone https://github.com/FortAwesome/Font-Awesome "$tmpdir/font-awesome"
#git clone https://github.com/zurb/foundation-icons "$tmpdir/foundation"

cp $tmpdir/typicons/src/font/typicons.ttf $mydir/resources/typicons/
cp $tmpdir/foundation/*/sass/*_foundicons.scss "$mydir/resources/foundation/"
cp $tmpdir/foundation/*/fonts/*.ttf "$mydir/resources/foundation/"


