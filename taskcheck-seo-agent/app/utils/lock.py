"""Single-instance lock voor de daemon.

Telegram staat maar één polling-sessie per bot toe: een tweede daemon levert
eindeloze Conflict-fouten op en laat scheduler-taken dubbel lopen. Een socket
bind is hiervoor betrouwbaarder dan een pid-bestand, omdat het OS de lock
vrijgeeft zodra het proces stopt — ook na een crash of kill.
"""

from __future__ import annotations

import os
import socket

DEFAULT_LOCK_PORT = 47653


def acquire_single_instance_lock(port: int | None = None) -> socket.socket | None:
    """Reserveer de lock. Retourneert None als de daemon al draait.

    De socket moet in leven blijven zolang het proces draait.
    """
    if port is None:
        port = int(os.getenv("DAEMON_LOCK_PORT", str(DEFAULT_LOCK_PORT)))

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    try:
        sock.bind(("127.0.0.1", port))
        sock.listen(1)
    except OSError:
        sock.close()
        return None
    return sock
