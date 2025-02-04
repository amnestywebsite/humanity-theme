#!/usr/bin/env sh

####################################################################
# Refreshes POT, updates PO from POT, and compiles PO into MO.     #
#                                                                  #
# NB: if running this script on OS X, first install GNU sed,       #
# following the instructions from Homebrew to add it to your PATH: #
# $  brew install gnu-sed                                          #
####################################################################

basedir="$(git rev-parse --show-toplevel)"

# remove changes that only touch refresh header and do nothing else:
remove_date_only_changes() {
  if ! git ls-files "$1" --error-unmatch > /dev/null 2>&1; then
    return
  fi

  changes=$(git diff "$1" | grep '^[+-][^+-]' | grep -vc '\(PO-Revision\|POT-Creation\)-Date')

  if [ "$changes" -eq 0 ]; then
    git checkout "$1" > /dev/null 2>&1
  fi
}

# scrap rubbish from gettext-generated file
fixup_header_information() {
  thedate=$(date '+%Y-%m-%d %H:%M%z')

  # replace placeholder information
  sed -i "s/FULL NAME <EMAIL@ADDRESS>//g" "$1"
  sed -i "s/LANGUAGE <LL@li.org>//g" "$1"
  sed -i "s/YEAR-MO-DA HO:MI+ZONE/$thedate/g" "$1"
  sed -i -e '1,4d' "$1"
}

# add headers required for usage in poedit
insert_poedit_headers() {
  # create temporary empty file for poedit headers
  touch "$basedir/wp-content/themes/humanity-theme/languages/header.txt"
  truncate -s 0 "$basedir/wp-content/themes/humanity-theme/languages/header.txt"

  # insert poedit headers
  tee "$basedir/wp-content/themes/humanity-theme/languages/header.txt" << EOF > /dev/null
"X-Generator: Poedit 2.2.1"
"X-Poedit-Basepath: .."
"X-Poedit-Flags-xgettext: --add-comments=translators:"
"X-Poedit-WPHeader: style.css"
"X-Poedit-SourceCharset: UTF-8"
"X-Poedit-KeywordsList: __;_e;_n:1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;esc_attr__;"
"esc_attr_e;esc_attr_x:1,2c;esc_html__;esc_html_e;esc_html_x:1,2c;_n_noop:1,2;"
"_nx_noop:3c,1,2;__ngettext_noop:1,2"
"PO-Revision-Date: "
"X-Poedit-SearchPath-0: ."
"X-Poedit-SearchPathExcluded-0: *.js"
EOF

  # backup POT file
  mv "$1" "$1.bak"
  # insert headers from backup POT file into new POT file
  sed -e '/^$/,$d' "$1.bak" > "$1"
  # append poedit headers to new POT file
  cat "$basedir/wp-content/themes/humanity-theme/languages/header.txt" >> "$1"
  # append everything after the headers in backup POT file into new POT file
  sed -e '1,27d' "$1.bak" >> "$1"

  # cleanup
  rm "$basedir/wp-content/themes/humanity-theme/languages/header.txt"
  rm "$1.bak"
}

touch "$basedir/wp-content/themes/humanity-theme/languages/_amnesty.pot"

# pass all found PHP files to gettext
find . -name "*.php" | grep -vi '^\.\/\.git' | xargs xgettext \
  --language="PHP" \
  --output="$basedir/wp-content/themes/humanity-theme/languages/_amnesty.pot" \
  --force-po \
  --add-location \
  --from-code="UTF-8" \
  --foreign-user \
  --copyright-holder="" \
  --add-comments="translators:" \
  --package-name="Amnesty WP Theme" \
  --msgid-bugs-address="@github:amnestywebsite/humanity-theme/issues" \
  -k_ \
  -kgettext \
  -kdgettext:2 \
  -kdcgettext:2 \
  -kngettext:1,2 \
  -kdngettext:2,3 \
  -kdcngettext:2,3 \
  -k__ \
  -k_e \
  -k_n:1,2 \
  -k_x:1,2c \
  -k_ex:1,2c \
  -k_nx:4c,1,2 \
  -kesc_attr__ \
  -kesc_attr_e \
  -kesc_attr_x:1,2c \
  -kesc_html__ \
  -kesc_html_e \
  -kesc_html_x:1,2c \
  -k_n_noop:1,2 \
  -k_nx_noop:3c,1,2 \
  -k__ngettext_noop:1,2

# cleanup temporary POT file
fixup_header_information "$basedir/wp-content/themes/humanity-theme/languages/_amnesty.pot"
insert_poedit_headers "$basedir/wp-content/themes/humanity-theme/languages/_amnesty.pot"

# copy to actual POT file
msgcat -o "$basedir/wp-content/themes/humanity-theme/languages/amnesty.pot" "$basedir/wp-content/themes/humanity-theme/languages/_amnesty.pot"
rm "$basedir/wp-content/themes/humanity-theme/languages/_amnesty.pot"

# if only the timestamp has changed, don't bother with it
remove_date_only_changes "$basedir/wp-content/themes/humanity-theme/languages/amnesty.pot"

# Merge changes with PO files:
for p in "$basedir"/wp-content/themes/humanity-theme/languages/*.po; do
  msgmerge --quiet -o "$p.tmp" --no-fuzzy-matching "$p" "$basedir/wp-content/themes/humanity-theme/languages/amnesty.pot"
  msgattrib --no-obsolete -o "$p" "$p.tmp"
  rm "$p.tmp"
  remove_date_only_changes "$p"
done

# validate and compile MO files:
for i in "$basedir"/wp-content/themes/humanity-theme/languages/*.po; do
  msgfmt "$i" -c -o "${i%.*}.mo"
done

echo "Language file refresh complete."
