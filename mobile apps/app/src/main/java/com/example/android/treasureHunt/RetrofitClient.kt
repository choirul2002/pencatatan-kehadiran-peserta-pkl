package com.example.android.treasureHunt

import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

//object RetrofitClient{
//    private const val BASE_URL = "https://jsonplaceholder.typicode.com/"
//
//    val intance:ApiService by lazy{
//        val retrofit = Retrofit.Builder()
//            .baseUrl(BASE_URL)
//            .addConverterFactory(GsonConverterFactory.create())
//            .build()
//        retrofit.create(ApiService::class.java)
//    }
//}

object RetrofitClient{
    private const val BASE_URL = "http://192.168.0.101/simaptapkl/public/service/"

    val intance:ApiService by lazy{
        val retrofit = Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
        retrofit.create(ApiService::class.java)
    }
}