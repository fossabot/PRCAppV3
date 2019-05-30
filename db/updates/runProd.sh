#!/bin/bash
# run the updates on the databases
psql devdemo     -f upd01.sql > upd01.demo.ret
psql devjaspal   -f upd01.sql > upd01.jaspal.ret
psql devtemplate -f upd01.sql > upd01.template.ret
psql devdemo2    -f upd01.sql > upd01.demo2.ret


psql devdemo     -f upd02.sql > upd02.demo.ret
psql devjaspal   -f upd02.sql > upd02.jaspal.ret
psql devtemplate -f upd02.sql > upd02.template.ret
psql devdemo2    -f upd02.sql > upd02.demo2.ret


psql devdemo     -f upd03.sql > upd03.demo.ret
psql devjaspal   -f upd03.sql > upd03.jaspal.ret
psql devtemplate -f upd03.sql > upd03.template.ret
psql devdemo2    -f upd03.sql > upd03.demo2.ret


psql devdemo     -f upd04.sql > upd04.demo.ret
psql devjaspal   -f upd04.sql > upd04.jaspal.ret
psql devtemplate -f upd04.sql > upd04.template.ret
psql devdemo2    -f upd04.sql > upd04.demo2.ret


psql devdemo     -f upd05.sql > upd05.demo.ret
psql devjaspal   -f upd05.sql > upd05.jaspal.ret
psql devtemplate -f upd05.sql > upd05.template.ret
psql devdemo2    -f upd05.sql > upd05.demo2.ret


psql devdemo     -f upd06.sql > upd06.demo.ret
psql devjaspal   -f upd06.sql > upd06.jaspal.ret
psql devtemplate -f upd06.sql > upd06.template.ret
psql devdemo2    -f upd06.sql > upd06.demo2.ret

psql devdemo     -f upd07.sql > upd07.demo.ret
psql devjaspal   -f upd07.sql > upd07.jaspal.ret
psql devtemplate -f upd07.sql > upd07.template.ret
psql devdemo2    -f upd07.sql > upd07.demo2.ret

