package com.web.movil

import android.os.Bundle
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONArray

class MainActivity : AppCompatActivity() {

    lateinit var recyclerView: RecyclerView
    lateinit var lista: ArrayList<Pelicula>
    lateinit var adapter: PeliculaAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        recyclerView = findViewById(R.id.recyclerView)
        recyclerView.layoutManager = LinearLayoutManager(this)

        lista = ArrayList()
        adapter = PeliculaAdapter(lista)
        recyclerView.adapter = adapter

        cargarPeliculas()
    }

    private fun cargarPeliculas() {

        val prefs = getSharedPreferences("FilmHub", MODE_PRIVATE)
        val token = prefs.getString("token", "")

        val url = "http://192.168.1.5:3000/api/peliculas"

        val request = object : StringRequest(
            Method.GET, url,
            { response ->

                val array = JSONArray(response)

                for (i in 0 until array.length()) {

                    val obj = array.getJSONObject(i)

                    if (obj.getString("estado") == "activa") {

                        val pelicula = Pelicula(
                            obj.getInt("id"),
                            obj.getString("nombre"),
                            obj.getString("genero"),
                            obj.getString("imagen"),
                            obj.getString("descripcion"),
                            obj.getString("link_trailer"),
                            obj.getString("estado")
                        )

                        lista.add(pelicula)
                    }
                }

                adapter.notifyDataSetChanged()

            },
            {
                Toast.makeText(this, "Error al cargar películas", Toast.LENGTH_SHORT).show()
            }
        ) {
            override fun getHeaders(): MutableMap<String, String> {
                val headers = HashMap<String, String>()
                headers["Authorization"] = "Bearer $token"
                return headers
            }
        }

        Volley.newRequestQueue(this).add(request)
    }
}