using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Viewable : MonoBehaviour {

	void OnTriggerEnter(Collider other)
	{
		if ((other.tag == "Enemy" || other.tag == "Ivan" || other.tag == "StarBro" || other.tag == "Spangle") && other.transform.Find("Gun") != null)
			other.transform.Find("Gun").gameObject.GetComponent<AimShoot> ().fireable = true;
	}
	void OnTriggerExit(Collider other)
	{
		if ((other.tag == "Enemy" || other.tag == "Ivan" || other.tag == "StarBro" || other.tag == "Spangle") && other.transform.Find("Gun") != null)
			other.transform.Find("Gun").gameObject.GetComponent<AimShoot> ().fireable = false;
		if (other.tag == "Bolt")
			Destroy (other.gameObject);
	}
}
