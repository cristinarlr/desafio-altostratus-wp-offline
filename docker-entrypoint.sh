#!/bin/bash
set -euo pipefail

# Función para instalar plugins usando WP-CLI
install_plugins() {
    local plugins=("$@")
    for plugin in "${plugins[@]}"; do
        wp plugin install "$plugin" --activate
    done
}

# Instalar y activar plugins
install_plugins simply-static simply-static-pro

# Ejecutar el comando que se pasa como argumento al script de entrada
exec "$@"
