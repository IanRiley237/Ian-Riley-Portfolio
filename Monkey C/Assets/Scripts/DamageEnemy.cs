using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class DamageEnemy : MonoBehaviour {

	public int damage = 2;

	void OnTriggerEnter (Collider other)
	{
		if (other.tag == "Boundary" || other.tag == "Player" || other.tag == "Bolt" || other.tag == "EnemyBolt")
			return;

		if (other.tag == "StarBro")
		{
			GameObject[] stars = GameObject.FindGameObjectsWithTag ("StarBro");
			stars [0].GetComponent<Health> ().health -= damage;

			if (stars.Length == 2)
			{
				stars [1].GetComponent<Health> ().health -= damage;

				if (other.gameObject.GetComponent<Health> ().health < other.gameObject.GetComponent<Health> ().maxHealth / 3)
				{
					Instantiate (other.GetComponent<Health>().explosion, transform.position, transform.rotation);
					Destroy (other.gameObject);
				}
			}

		} else
		{
			other.gameObject.GetComponent<Health> ().health -= damage;
		}

		// Destroy the bolt
		Destroy (gameObject);
	}
}
