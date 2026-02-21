package com.web.movil

data class Pelicula(
    val id: Int,
    val nombre: String,
    val genero: String,
    val imagen: String,
    val descripcion: String,
    val link_trailer: String,
    val estado: String
)