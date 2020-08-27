package com.example.inner.ep_trgovina;

import java.lang.Override;import java.lang.String;import java.util.Locale;


public class Izdelek {
public final int id;
public final String ime, opis, cena, url;

		
    public Izdelek(int id, String ime, double cena, String opis, String url){
        this.id  = id;
        this.ime = ime;
        this.opis = opis;
        this.cena = String.valueOf(cena) + "€";
        if(url.isEmpty()){
            this.url = null;
        }else this.url = url;
    }

    //overridam to stirng in z njim delegiram kako bo zigleda lizpis na ekranu
    @Override
    public String toString() {
                return ime+" "+cena+" VEČ->";
    }
    public String getIme(){return this.ime;}
    public String getOpis(){return this.opis;}
    public String getCena(){return this.cena;}
    public String getUrl(){return this.url;}
}
