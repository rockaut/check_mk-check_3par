#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-

register_check_parameters(
    subgroup_storage,
    "3par_cpgs",
    _("3Par - CPGs"),
    Dictionary(
        elements = filesystem_elements + [
            ( "cpgstatus_warning",
              DropdownChoice(
                  title = _("Warn on CPG status"),
                  default_value = 2,
                  choices = [
                     ( 3,   _("Failed") ),
                     ( 2,   _("Degraded") ),
                     ( 1,   _("Normal") ),
                  ]
              )
            ),
            ( "cpgstatus_critical",
              DropdownChoice(
                  title = _("Critical on CPG status"),
                  default_value = 3,
                  choices = [
                     ( 3,   _("Failed") ),
                     ( 2,   _("Degraded") ),
                     ( 1,   _("Normal") ),
                  ]
              )
            ),
        ],
        hidden_keys = ["flex_levels", "trend_perfdata", "trend_showtimeleft", "trend_timeleft", "trend_perc", "trend_mb", "trend_range", "magic", "magic_normsize", "levels_low", "inodes_levels" ]
    ),
    TextAscii(
        title = _("Name of the CPG"),
    ),
    "dict"
)

register_check_parameters(
    subgroup_storage,
    "3par_ports",
    _("3Par - Ports"),
    Dictionary(
        elements = [
            ( "linkState-1",
              DropdownChoice(
                  title = _("State on link state - CONFIG_WAIT"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),
            ( "linkState-2",
              DropdownChoice(
                  title = _("State on link state - ALPA_WAIT"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-3",
              DropdownChoice(
                  title = _("State on link state - LOGIN_WAIT"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-4",
              DropdownChoice(
                  title = _("State on link state - READY"),
                  default_value = 0,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-5",
              DropdownChoice(
                  title = _("State on link state - LOSS_SYNC"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-6",
              DropdownChoice(
                  title = _("State on link state - ERROR_STATE"),
                  default_value = 3,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-7",
              DropdownChoice(
                  title = _("State on link state - XXX"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-8",
              DropdownChoice(
                  title = _("State on link state - NONPARTICIPATE"),
                  default_value = 0,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-9",
              DropdownChoice(
                  title = _("State on link state - COREDUMP"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-10",
              DropdownChoice(
                  title = _("State on link state - OFFLINE"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-12",
              DropdownChoice(
                  title = _("State on link state - IDLE_FOR_RESET"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-11",
              DropdownChoice(
                  title = _("State on link state - FWDEAD"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-13",
              DropdownChoice(
                  title = _("State on link state - DHCP_IN_PROGRESS"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "linkState-14",
              DropdownChoice(
                  title = _("State on link state - PENDING_RESET"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),
            ########################################
            ( "failoverState-1",
              DropdownChoice(
                  title = _("State on failover - NORMAL"),
                  default_value = 0,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "failoverState-2",
              DropdownChoice(
                  title = _("State on failover - FAILOVER_PENDING"),
                  default_value = 2,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "failoverState-3",
              DropdownChoice(
                  title = _("State on failover - FAILED_OVER"),
                  default_value = 2,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "failoverState-4",
              DropdownChoice(
                  title = _("State on failover - ACTIVE"),
                  default_value = 2,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "failoverState-5",
              DropdownChoice(
                  title = _("State on failover - ACTIVE_DOWN"),
                  default_value = 2,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "failoverState-6",
              DropdownChoice(
                  title = _("State on failover - ACTIVE_FAILED"),
                  default_value = 2,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),( "failoverState-7",
              DropdownChoice(
                  title = _("State on failover - FAILBACK_PENDING"),
                  default_value = 1,
                  choices = [
                     (2, _('CRITICAL')),
                     (1, _('WARNING')),
                     (0, _('OK')),
                  ],
              )
            ),
        ],
    ),
    TextAscii(
        title = _("Name of the Port"),
    ),
    "dict"
)