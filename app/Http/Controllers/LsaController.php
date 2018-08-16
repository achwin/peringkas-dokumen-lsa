<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LsaController extends Controller
{
    public function index()
    {
    	$text = "Ada berita duka cita yang juga mewarnai dunia hiburan Tanah Air pagi ini. Salah satu chef nyentrik dan lucu, Harada meninggal dunia. Kabar duka itu disampaikan oleh putrinya, Ayumi Harada melalui Instagramnya. Ayumi memposting foto saat dirinya bersama sang ayah. Harada menjadi salah satu celebrity chef yang namanya sudah sangat populer. Tampilannya pun sangat unik dan berbeda dengan selalu menggunakan baju khas Jepang. Namun Harada yang ceria sempat tak berdaya karena sakit. Hal itu juga diceritakan oleh Ayumi lewat akun Instagramnya. Kabar duka ini pun mendapat banyak ucapan belasungkawa. Banyak netizen yang berduka dengan kabar meninggalnya-sang juru masak nyentrik itu.";
    	// create sentence detector
		$sentenceDetectorFactory = new \Sastrawi\SentenceDetector\SentenceDetectorFactory();
		$sentenceDetector = $sentenceDetectorFactory->createSentenceDetector();

		// detect sentence
		$sentences = $sentenceDetector->detect($text);
		foreach ($sentences as $i => $sentence) {
			$sentences[$i] = mb_strtolower($sentence);
		}

		$words = [];
		foreach ($sentences as $i => $sentence) {
			$sentence = preg_replace('/[^a-z]/', ' ', $sentence);
			$words[$i] = array_merge(explode(' ', $sentence));
		}
		
		foreach ($words as $i => $word) {
			$words[$i] = array_filter($word);
			$words[$i] = array_values($words[$i]);
		}
		$filename = public_path('id.stopwords.02.01.2016.txt');
		$stopwords = file($filename, FILE_IGNORE_NEW_LINES);

		foreach ($words as $i => $word) {
			foreach ($word as $j => $w) {
				if (in_array($w, $stopwords)) {
					unset($words[$i][$j]);
				}
			}
			$words[$i] = array_values($words[$i]);
		}
		
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer  = $stemmerFactory->createStemmer();

		foreach ($words as $i => $word) {
			foreach ($word as $j => $w) {
				$words[$i][$j]   = $stemmer->stem($w);
			}
		}
		dd($words);
    }
}