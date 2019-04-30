# yaml-standardiser
Apply some basic standards to your yaml files

Add to your project with `composer require --dev chrisdbrown/yaml-standardiser`

Run with `vendor/bin/yaml-standardiser file-to-edit.yaml file2.yaml`

Passing a directory will analyse all files with `.yml` or `.yaml` extensions in that directory and any subdirectories

### Currently implemented

- top level key alphabetisation on single file
- multi-file support

### Todo

- prioritise certain keys in ordering
- multi-level key alphabetisation
- indentation
- spacing between key blocks
- object standards
- set config by file
- improved reporting
- human-readable diff generation
- patch file generation
