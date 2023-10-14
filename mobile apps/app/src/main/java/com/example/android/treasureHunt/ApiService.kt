package com.example.android.treasureHunt
import retrofit2.Call
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST

interface ApiService {
    @GET("coba.php")
    fun getData(): Call<DataModel>
}