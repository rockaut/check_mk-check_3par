#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-

register_check_parameters(
    subgroup_storage,
    "3par_cpgs",
    _("Status of 3Par CPGs"),
    Dictionary(
        elements = filesystem_elements + [
            ( "cpgstatus_warning",
              DropdownChoice(
                  title = _("Warn on CPG status"),
                  choices = [
                     ( "3",   _("Failed") ),
                     ( "2",   _("Degraded") ),
                     ( "1",   _("Normal") ),
                  ]
              )
            ),
            ( "cpgstatus_critical",
              DropdownChoice(
                  title = _("Critical on CPG status"),
                  choices = [
                     ( "3",   _("Failed") ),
                     ( "2",   _("Degraded") ),
                     ( "1",   _("Normal") ),
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
