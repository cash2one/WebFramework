#!/bin/bash

# copy me to the click service root dir

java -server -classpath ./lib/*:./classes/ outfox.ead.click.SyndIdExtractor ead-aes-key.1195118241296 access.log
