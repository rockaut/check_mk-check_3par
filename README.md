> WORD OF CAUTION: currently heavy under development! Several breaking changes may occure without asuring backward compatibility.

# Check_MK Agent for Graylog REST API

This check plugin for Check_MK monitors several important stats and stati of your HPE 3Par cluster/system.

The plugin has been tested to work with check_mk and nagios ( with Thruk as frontend ) installed through OMD. For detailed versions see "Prerequisites".

## Prerequisites
- Build and tested only on OMD Consol-Labs Edition 2.10 ( CMK 1.2.6p12 )
- Tested against HP 3Par 7400 with 3.2.1.292
- Python Modules: simplejson, httplib, urllib2, pprint, sys, os, getopt, socket

## Installation

### Sourcefiles

1. drop the files in your OMD site ( /opt/omd/sites/awesomesite )
2. create new host for your 3Par
3. enable 3Par wsapi and add user ( only read permissions needed )
4. test agent_3par on omd server shell
 * /opt/omd/sites/awesomesite/local/share/check_mk/agents/special/agent_3par -H my3par -U roUser -P roPassword
5. add individual program call datasource rule with above command

### Check_MK Package

1. download package from https://github.com/rockaut/check_mk-3par/releases
2. install with cmk ( cli or gui )
 * cmk -vP install 3par-whateverversion.mkp

## Working
- Volumes
- CPGs
- System
- Hosts
- Ports
- FlashCache