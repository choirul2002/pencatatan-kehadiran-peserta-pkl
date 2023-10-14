package com.example.android.treasureHunt

import android.content.Context
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.net.Uri
import android.os.Environment
import android.provider.MediaStore
import android.util.Base64
import android.util.Log
import android.widget.ImageView
import androidx.core.content.FileProvider
import java.io.ByteArrayOutputStream
import java.io.File
import java.text.SimpleDateFormat
import java.util.*


class MediaHelper(context:Context) {
    val context=context
    var namaFile = ""
    var fileUri = Uri.parse("")
    val RC_CAMERA = 100

    fun RcGallery():Int{
        return REQ_CODE_GALLERY
    }

    fun getMyFileName(): String{
        return this.namaFile
    }

    fun getRcCamera() : Int{
        return this.RC_CAMERA
    }

    fun bitmapToString(bmp:Bitmap):String{
        val outputStream = ByteArrayOutputStream()
        bmp.compress(Bitmap.CompressFormat.JPEG,60,outputStream)
        val byteArray = outputStream.toByteArray()
        return Base64.encodeToString(byteArray, Base64.DEFAULT)
    }

    fun getBitmapToStringIzin(uri: Uri?):String{
        var bmp = MediaStore.Images.Media.getBitmap(
            this.context.contentResolver,uri)
        var dim = 720
        if (bmp.height> bmp.width){
            bmp = Bitmap.createScaledBitmap(bmp,
                (bmp.width*dim).div(bmp.height),dim,true)
        }else{
            bmp = Bitmap.createScaledBitmap(bmp,
                dim,(bmp.height*dim).div(bmp.width),true)
        }

        return bitmapToString(bmp)
    }

    fun getBitmapToString(uri: Uri?, imv: ImageView):String{
        var bmp = MediaStore.Images.Media.getBitmap(
            this.context.contentResolver,uri)
        var dim = 720
        if (bmp.height> bmp.width){
            bmp = Bitmap.createScaledBitmap(bmp,
                (bmp.width*dim).div(bmp.height),dim,true)
        }else{
            bmp = Bitmap.createScaledBitmap(bmp,
                dim,(bmp.height*dim).div(bmp.width),true)
        }
        imv.setImageBitmap(bmp)
        return bitmapToString(bmp)
    }

    fun getBitmapToStringIzinKamera(text:String):String{
        var bmp = Bitmap.createBitmap(100, 100, Bitmap.Config.ARGB_8888)
        bmp = BitmapFactory.decodeFile(text)
        var dim = 720
        if (bmp.height> bmp.width){
            bmp = Bitmap.createScaledBitmap(bmp,
                (bmp.width*dim).div(bmp.height),dim,true)
        }else{
            bmp = Bitmap.createScaledBitmap(bmp,
                dim,(bmp.height*dim).div(bmp.width),true)
        }

        return bitmapToString(bmp)
    }

    fun getoutputMediaFile(): File?{
        val mediaStorageDir = File(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DCIM), "Camera")
        if(!mediaStorageDir.exists())
            if(!mediaStorageDir.mkdirs()){
                Log.e("mkdir","Gagal membuat direktori")
            }
        val mediaFile = File(mediaStorageDir.path+ File.separator+"${this.namaFile}")

        return mediaFile
    }

    fun getOutputMediaFileUri(): Uri{
        val timeStamp = SimpleDateFormat("yyyyMMddHHmmss", Locale.getDefault()).format(Date())
        this.namaFile = "DC_${timeStamp}.jpg"
        this.fileUri = Uri.fromFile(getoutputMediaFile())
        return this.fileUri
    }

    fun getBitmapToString(imV: ImageView,text:String): String{
        var bmp = Bitmap.createBitmap(100, 100, Bitmap.Config.ARGB_8888)
        bmp = BitmapFactory.decodeFile(text)
        var dim = 720
        if(bmp.height > bmp.width){
            bmp = Bitmap.createScaledBitmap(bmp,(bmp.width*dim).div(bmp.height), dim, true)
        }else{
            bmp = Bitmap.createScaledBitmap(bmp, dim, (bmp.height*dim).div(bmp.width), true)
        }
        imV.setImageBitmap(bmp)
        return bitmapToString(bmp)
    }

    companion object{
        const val  REQ_CODE_GALLERY = 0
    }
}