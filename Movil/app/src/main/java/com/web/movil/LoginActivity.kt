package com.web.movil

import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONObject
class LoginActivity : AppCompatActivity() {

    lateinit var etCorreo: EditText
    lateinit var etClave: EditText
    lateinit var btnLogin: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        etCorreo = findViewById(R.id.etCorreo)
        etClave = findViewById(R.id.etClave)
        btnLogin = findViewById(R.id.btnLogin)

        btnLogin.setOnClickListener {
            login()
        }
    }

    private fun login() {

        val url = "http://192.168.137.244:3000/api/login"

        val request = object : StringRequest(
            Method.POST, url,
            { response ->

                val json = JSONObject(response)
                val token = json.getString("token")

                val prefs = getSharedPreferences("FilmHub", MODE_PRIVATE)
                prefs.edit().putString("token", token).apply()

                startActivity(Intent(this, MainActivity::class.java))
                finish()

            },
            {
                Toast.makeText(this, "Error de login", Toast.LENGTH_SHORT).show()
            }
        ) {
            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["correo"] = etCorreo.text.toString()
                params["clave"] = etClave.text.toString()
                return params
            }
        }

        Volley.newRequestQueue(this).add(request)
    }
}