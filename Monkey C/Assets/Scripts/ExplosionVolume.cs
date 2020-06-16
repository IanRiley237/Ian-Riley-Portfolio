using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ExplosionVolume : MonoBehaviour {

	// Use this for initialization
	void Start () {

		GetComponent<AudioSource> ().volume = Data.effectVolume * (name == "Asteroid Explosion(Clone)" ? 0.8f : 0.35f);
	}
	
	// Update is called once per frame
	void Update () {
		GetComponent<AudioSource> ().volume = Data.effectVolume * (name == "Asteroid Explosion(Clone)" ? 0.8f : 0.35f);
	}
}
