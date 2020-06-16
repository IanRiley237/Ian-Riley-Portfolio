using UnityEngine;
using System.Collections;

public class DestroyByContact : MonoBehaviour
{
	public int scoreValue, damage = 1;

	void Start ()
	{
	}

	void Update()
	{
		
	}

	void OnTriggerEnter (Collider other)
	{
		if (other.tag == "Boundary" || other.tag == "Enemy" || other.tag == "Bolt" || other.tag == "EnemyBolt" || other.tag == "Ivan" || other.tag == "StarBro" || other.tag == "Spangle")

		{
			return;
		}

		if (other.tag == "Asteroid")
		{
			if (tag == "Enemy")
			{
				Instantiate (other.GetComponent<Health>().explosion, other.transform.position, transform.rotation);
				Destroy (other.gameObject);
			}
			return;
		}


		if (other.tag == "Player")
		{
			other.GetComponent<Health> ().health -= damage;
			other.GetComponent<Rigidbody>().transform.localPosition = new Vector3(0f, -1.1f, -20f);
			other.GetComponent<PlayerControl> ().CallClear (2.0f);
			Instantiate (other.GetComponent<PlayerControl> ().screenClearEffect, other.transform.position, other.GetComponent<PlayerControl> ().screenClearEffect.transform.rotation, other.transform);
			//other.GetComponent<PlayerControl> ().Flasher (2.0f);
		}

		if (tag == "Bolt" || tag == "EnemyBolt")
		{
			Destroy (gameObject);
		}
		else
			GetComponent<Health> ().health -= 2;
			
	}
}