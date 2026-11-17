#!/bin/sh
set -e

if ! command -v php >/dev/null 2>&1; then
  echo "PHP ei ole asennettu tai se ei ole PATH-ympäristömuuttujassa." >&2
  exit 1
fi

cd "$(dirname "$0")"
echo "Avaa http://127.0.0.1:8000/course-index.php"
echo "Pysäytä palvelin painamalla Ctrl+C."
exec php -S 127.0.0.1:8000
