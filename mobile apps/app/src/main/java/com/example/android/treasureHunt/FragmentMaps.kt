package com.example.android.treasureHunt

import android.Manifest
import android.content.pm.PackageManager
import android.graphics.Bitmap
import android.graphics.Canvas
import android.graphics.Color
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.fragment.app.Fragment
import com.getbase.floatingactionbutton.FloatingActionButton
import com.google.android.gms.maps.CameraUpdateFactory
import com.google.android.gms.maps.GoogleMap
import com.google.android.gms.maps.OnMapReadyCallback
import com.google.android.gms.maps.SupportMapFragment
import com.google.android.gms.maps.model.BitmapDescriptorFactory
import com.google.android.gms.maps.model.CircleOptions
import com.google.android.gms.maps.model.LatLng
import com.google.android.gms.maps.model.MarkerOptions

class FragmentMaps: Fragment(), OnMapReadyCallback {
    lateinit var thisParent:HuntMainActivity
    lateinit var v: View
    private val ACCESS_LOCATION_REQUEST_CODE = 10001
    var gMap : GoogleMap? = null
    lateinit var mapFragment : SupportMapFragment

    override fun onMapReady(p0: GoogleMap?) {
        gMap = p0
        val data = arguments
        val lat = data?.get("latitude").toString()
        val long = data?.get("longitude").toString()
        val radius = data?.get("radius").toString()
        val title = data?.get("title").toString()
        val koordinat = LatLng(lat.toDouble(),long.toDouble())
//        val koordinat = LatLng(-7.80793,112.0655461)

        if (ContextCompat.checkSelfPermission(
                thisParent,
                Manifest.permission.ACCESS_FINE_LOCATION
            ) == PackageManager.PERMISSION_GRANTED
        ) {
            enableUserLocation()
            addMarker(koordinat, title)
            addCircle(koordinat, radius.toFloat())
//            addCircle(koordinat, 100f)
            gMap!!.moveCamera(CameraUpdateFactory.newLatLngZoom(koordinat, 17f))
        } else {
            if (ActivityCompat.shouldShowRequestPermissionRationale(
                    thisParent,
                    Manifest.permission.ACCESS_FINE_LOCATION
                )
            ) {
                //We can show user a dialog why this permission is necessary
                ActivityCompat.requestPermissions(
                    thisParent,
                    arrayOf(Manifest.permission.ACCESS_FINE_LOCATION),
                    ACCESS_LOCATION_REQUEST_CODE
                )
            } else {
                ActivityCompat.requestPermissions(
                    thisParent,
                    arrayOf(Manifest.permission.ACCESS_FINE_LOCATION),
                    ACCESS_LOCATION_REQUEST_CODE
                )
            }
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        thisParent = activity as HuntMainActivity
        v = inflater.inflate(R.layout.fragment_maps,container,false)

        mapFragment = childFragmentManager.findFragmentById(R.id.fragmen) as SupportMapFragment
        mapFragment.getMapAsync(this)

        return v
    }

    private fun enableUserLocation(){
        if (ActivityCompat.checkSelfPermission(
                thisParent,
                Manifest.permission.ACCESS_FINE_LOCATION
            ) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(
                thisParent,
                Manifest.permission.ACCESS_COARSE_LOCATION
            ) != PackageManager.PERMISSION_GRANTED
        ) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return
        }
        gMap!!.setMyLocationEnabled(true)
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray
    ) {
        if (requestCode == ACCESS_LOCATION_REQUEST_CODE) {
            if (grantResults.size > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                enableUserLocation()
            } else {
                //We can show a dialog that permission is not granted...
            }
        }

        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
    }

    private fun addMarker(latLng: LatLng, judul:String){
        val markerOptions = MarkerOptions().position(latLng).title(judul).icon(BitmapDescriptorFactory.fromBitmap(getMarkerBitmapFromView2()))
        gMap!!.addMarker(markerOptions)
    }

    private fun addCircle(latLng: LatLng, radius: Float){
        val circleOptions = CircleOptions()
        circleOptions.center(latLng)
        circleOptions.radius(radius.toDouble())
        circleOptions.strokeColor(Color.rgb(83, 127, 231))
        circleOptions.fillColor(Color.argb(40,83,127,231))
        circleOptions.strokeWidth(4f)
        gMap!!.addCircle(circleOptions)
    }

    private fun getMarkerBitmapFromView2(): Bitmap {
        val view = layoutInflater.inflate(R.layout.marker_layout2, null)

        val displayMetrics = resources.displayMetrics
        view.measure(displayMetrics.widthPixels, displayMetrics.heightPixels)
        view.layout(0, 0, displayMetrics.widthPixels, displayMetrics.heightPixels)

        val bitmap = Bitmap.createBitmap(view.measuredWidth, view.measuredHeight, Bitmap.Config.ARGB_8888)
        val canvas = Canvas(bitmap)
        view.draw(canvas)

        return bitmap
    }
}