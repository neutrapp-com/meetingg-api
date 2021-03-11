<?php

namespace Meetingg\Library;

class Permissions
{
    const READ_MESSAGES      = 0x00000001;
    const SEND_MESSAGES      = 0x00000002;
    const EDIT_MESSAGES      = 0x00000002;
    const DROP_MESSAGES      = 0x00000002;
    const INVITE_MEMBERS     = 0x00000008;
    const KICK_MEMBERS       = 0x00000010;
    const BAN_MEMBERS        = 0x00000020;
    const ADMINISTRATOR      = 0x00000040;
}
