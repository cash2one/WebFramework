#!/bin/bash

cd project
java -cp classes:lib/* outfox.ead.click.ClickInfo "$1" "$2"
# java -cp classes:lib/* outfox.ead.click.ClickInfo "detail" "http://nc107x.corp.youdao.com:18382/clk/request.s?k=hxYPakDeEZOSMi8V%2BJC21OA4Mx15Avt6BMQtlpvsiEEXC7c79fo8muXIkpqkijJanOZTdV8m7jyrHBNUHdE5OKR8z5CzdeSfj6yo%2F0oaghrWCtGU7OUozoqyAbqzzwDbZJBrITgHZtDVzR2hxBbXIPzVnJCi7EfFjT1G7HPoWX%2BCz85TF%2FB4mIbBPLjrGKeBMGC9xH59kIfTurmvhou6tcpit0opvI3v%2F7kwLVB%2BxPgiEvFRkdh3mgYZWo4Sqd2mmulkkuOKxdI%2F9Do3N%2B2L2O2YJrVkcmNAMEV8oxwXHMLHTamlaqoUoqMgGw4b262yE1GUT68710PjLimrlnje%2FBo5Td%2FdIlrndrUCgOznRwS%2FiInaus0VzUoreFhq0HRf6jrbumsI9K4cVqdZu9WWc3LT0jHP6O2Zgq4da6dCF5%2BNOpcBkMZ3wYrNmbm66Fsc18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq%2FBMpJuye5JOSiDMHBWBmuVlpdUX7FUs8fLVOkL%2FcDnzYyC4p3x3qzMFbJFUDIPFXYNchHbwAMSLVsxQpwPRv%2B6f%2B9x4akUv0yqJtfVjrap2A%3D%3D&d=http%3A%2F%2Fwww.5i591.net%2F%3Ftg%3Dyd&s=1"
