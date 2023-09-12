package com.example.android.treasureHunt

import android.app.Activity
import android.app.AlertDialog

class LoadingDialog(val mActivity: Activity) {
    private lateinit var isDialog:AlertDialog

    fun startLoading(){
        val inflater = mActivity.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading,null)

        val builder = AlertDialog.Builder(mActivity)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isDialog = builder.create()
        isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isDialog.show()
    }

    fun isDismiss(){
        isDialog.dismiss()
    }
}