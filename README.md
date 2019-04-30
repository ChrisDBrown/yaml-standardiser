# yaml-standardiser
Apply some basic standards to your yaml files

Add to your project with `composer require --dev chrisdbrown/yaml-standardiser`

Run with `vendor/bin/yaml-standardiser file-to-edit.yaml`

Use `--dry-run=1` to show errors without editing the file

### Currently implemented

- top level key alphabetisation on single file

### Todo

- prioritise certain keys in ordering
- multi-file support
- multi-level key alphabetisation
- indentation
- spacing between key blocks
- object standards
- set config by file
- improved reporting
- human-readable diff generation
- patch file generation
