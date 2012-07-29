#!/bin/bash

find mod_loadjquery25 ! -path "*.svn*" ! -path "*nbproject*" ! -name ".DS_Store" -print | zip mod_loadjquery25 -@
