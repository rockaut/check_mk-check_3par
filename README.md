> WORD OF CAUTION: currently heavy under development! Several breaking changes may occure without asuring backward compatibility.

# Check_MK Agent for Graylog REST API

This check plugin for Check_MK monitors several important stats and stati of your graylog cluster/system.

The plugin has been tested to work with check_mk and nagios ( with Thruk as frontend ) installed through OMD. For detailed versions see "Prerequisites".

## Prerequisites
- Build and tested only on OMD Consol-Labs Edition 2.10 ( CMK 1.2.6p12 / Thruk / Nagios )
- Tested against Graylog Appliance ( Version 2.0.3 )
- Python Modules: simplejson, httplib, urllib2, pprint, sys, os, getopt, socket

## Installation

### Sourcefiles

1. drop the files in your OMD site ( /opt/omd/sites/awesomesite )
2. create new host for your Graylog Server.
3. add nagios user to graylog
4. test agent_graylog on omd server shell
 * /opt/omd/sites/awesomesite/local/share/check_mk/agents/special/agent_graylog -H mygraylog -U roUser -P roPassword
5. add individual program call datasource rule with above command

### Check_MK Package

1. download package from https://github.com/rockaut/check_mk-check_graylog/releases
2. install with cmk ( cli or gui )
 * cmk -vP install check_graylog-whateverversion.mkp
