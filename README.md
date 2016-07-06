# Check_MK Agent for 3Par over WSAPI

## Prerequisites
- Build and tested only on OMD Consol-Labs Edition 2.10 ( CMK 1.2.6p12 )
- Tested against HP 3Par 7400 with 3.2.1.292
- Python Modules: simplejson, httplib, urllib2, pprint, sys, os, getopt, socket

## Installation

1. drop the files in your OMD site ( /opt/omd/sites/awesomesite )
2. create new host for your 3Par
3. enable 3Par wsapi and add user ( only read permissions needed )
4. test agent_3par on omd server shell
  * /opt/omd/sites/awesomesite/local/share/check_mk/agents/special/agent_3par -H my3par -U roUser -P roPassword
5. add individual program call datasource rule with above command

## Working
- Volumes
- CPGs
- System
- Hosts
- Ports

## TODO
- docs
- dictionary parameters
- check_mk package
