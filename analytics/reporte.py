import os
import sys

import matplotlib.pyplot as plt
import mysql.connector
import pandas as pd

if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8')

DB_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'database': 'rinconcito_perruno',
}

COLOR_ESTADO = {
    'pendiente': '#FFA500',
    'confirmada': '#4CAF50',
    'en_curso': '#2196F3',
    'finalizada': '#9C27B0',
    'cancelada': '#F44336',
}

COLOR_SERVICIO = '#8B4513'

QUERY_RESERVAS_POR_ESTADO = """
    SELECT estado, COUNT(*) as total
    FROM reservas
    GROUP BY estado
"""

QUERY_INGRESOS_POR_SERVICIO = """
    SELECT s.nombre, COUNT(r.id) as reservas,
           COALESCE(SUM(p.importe), 0) as ingresos
    FROM servicios s
    LEFT JOIN reservas r ON r.id_servicio = s.id
    LEFT JOIN pagos p ON p.id_reserva = r.id AND p.estado = 'completado'
    GROUP BY s.id, s.nombre
"""

QUERY_PAGOS_POR_METODO = """
    SELECT metodo, COUNT(*) as total, SUM(importe) as importe_total
    FROM pagos
    GROUP BY metodo
"""


def conectar():
    try:
        return mysql.connector.connect(**DB_CONFIG)
    except mysql.connector.Error as err:
        print(f'Error al conectar con MySQL: {err}')
        sys.exit(1)


def ejecutar_consulta(conexion, consulta):
    cursor = conexion.cursor(dictionary=True)
    cursor.execute(consulta)
    filas = cursor.fetchall()
    cursor.close()
    return filas


def graficar_reservas_por_estado(ax, filas):
    ax.set_title('Reservas por estado')

    if not filas:
        ax.text(0.5, 0.5, 'Sin datos disponibles', ha='center', va='center', transform=ax.transAxes)
        return

    estados = [fila['estado'] for fila in filas]
    totales = [fila['total'] for fila in filas]
    colores = [COLOR_ESTADO.get(estado, '#999999') for estado in estados]

    ax.barh(estados, totales, color=colores)
    ax.set_xlabel('Nº de reservas')


def graficar_ingresos_por_servicio(ax, filas):
    ax.set_title('Ingresos por servicio (€)')

    if not filas:
        ax.text(0.5, 0.5, 'Sin datos disponibles', ha='center', va='center', transform=ax.transAxes)
        return

    nombres = [fila['nombre'] for fila in filas]
    ingresos = [float(fila['ingresos']) for fila in filas]

    barras = ax.bar(nombres, ingresos, color=COLOR_SERVICIO)
    ax.set_ylabel('Ingresos (€)')
    ax.tick_params(axis='x', rotation=20)

    for barra, ingreso in zip(barras, ingresos):
        ax.text(
            barra.get_x() + barra.get_width() / 2,
            barra.get_height(),
            f'{ingreso:.0f}€',
            ha='center', va='bottom',
        )


def graficar_pagos_por_metodo(ax, filas):
    ax.set_title('Distribución de pagos por método')

    if not filas:
        ax.text(0.5, 0.5, 'Sin datos de pago aún', ha='center', va='center', transform=ax.transAxes)
        return

    metodos = [fila['metodo'] for fila in filas]
    totales = [fila['total'] for fila in filas]

    ax.pie(totales, labels=metodos, autopct='%1.1f%%', startangle=90)


def main():
    conexion = conectar()

    try:
        reservas_por_estado = ejecutar_consulta(conexion, QUERY_RESERVAS_POR_ESTADO)
        ingresos_por_servicio = ejecutar_consulta(conexion, QUERY_INGRESOS_POR_SERVICIO)
        pagos_por_metodo = ejecutar_consulta(conexion, QUERY_PAGOS_POR_METODO)
    finally:
        conexion.close()

    fig, (ax1, ax2, ax3) = plt.subplots(1, 3, figsize=(15, 5), facecolor='white')
    fig.suptitle('Rinconcito Perruno — Analítica de Negocio', fontsize=16, fontweight='bold')

    graficar_reservas_por_estado(ax1, reservas_por_estado)
    graficar_ingresos_por_servicio(ax2, ingresos_por_servicio)
    graficar_pagos_por_metodo(ax3, pagos_por_metodo)

    plt.tight_layout()

    directorio = os.path.dirname(os.path.abspath(__file__))
    ruta_png = os.path.join(directorio, 'reporte.png')
    ruta_csv = os.path.join(directorio, 'reservas_por_estado.csv')

    fig.savefig(ruta_png, dpi=150)

    pd.DataFrame(reservas_por_estado).to_csv(ruta_csv, index=False)

    total_reservas = sum(fila['total'] for fila in reservas_por_estado)
    ingresos_totales = sum(float(fila['ingresos']) for fila in ingresos_por_servicio)

    print('Reporte generado: analytics/reporte.png')
    print('CSV exportado: analytics/reservas_por_estado.csv')
    print(f'Total reservas: {total_reservas}')
    print(f' Ingresos totales: {ingresos_totales:.0f}€')


if __name__ == '__main__':
    main()
