package com.example.android.treasureHunt

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity

class MaintenanceActivity:AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_maintenance)
        supportActionBar?.hide()
    }
}