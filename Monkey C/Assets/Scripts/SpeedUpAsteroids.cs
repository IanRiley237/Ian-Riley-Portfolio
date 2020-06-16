using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SpeedUpAsteroids : MonoBehaviour {

	// Use this for initialization
	void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {
		if (GetComponent<GameController> ().Path [0].delaySpawn > 0.8f)
			GetComponent<GameController> ().Path [0].delaySpawn -= 0.001f;
	}
}
