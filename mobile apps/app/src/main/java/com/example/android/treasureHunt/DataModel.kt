package com.example.android.treasureHunt

data class DataModel(val datas: List<Data>) {
    data class Data(val id: Int?, val nama: String?)
}