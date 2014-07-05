#git subsplit available here : https://github.com/dflydev/git-subsplit
git subsplit init git@github.com:RocketPropelledTortoise/UI.git
git subsplit publish src/Assets:git@github.com:RocketPropelledTortoise/Assets.git
git subsplit publish src/Forms:git@github.com:RocketPropelledTortoise/Forms.git
git subsplit publish src/Foundation:git@github.com:RocketPropelledTortoise/Foundation.git
git subsplit publish src/Script:git@github.com:RocketPropelledTortoise/Script.git
git subsplit publish src/Table:git@github.com:RocketPropelledTortoise/Table.git
git subsplit publish src/Taxonomy:git@github.com:RocketPropelledTortoise/TaxonomyUI.git
rm -rf .subsplit/
