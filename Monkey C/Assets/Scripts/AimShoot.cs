using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class AimShoot : MonoBehaviour
{

	public GameObject shot;
	public float fireRate, timeFiring = 1, timePaused = 0, nextFire;
	public bool aimable, fireable = false, broken = false;

	private Vector3 shotSpawn;
	private float nextBurst, timeStored;

	// Use this for initialization
	void Start()
	{
	}

	void Awake ()
	{
		if (fireRate > 0)
			fireRate = 1 / fireRate;
		else
			fireRate = 0;
		nextBurst = Time.time + 0.2f;
	}

	// Update is called once per frame
	void Update()
	{
		GetComponentInParent<AudioSource> ().volume = Data.effectVolume * 0.2f;
	}
	void FixedUpdate ()
	{
		if (aimable && GameObject.Find("PlayerCore") != null)
		{ // If the weapon can aim, point it at the player
			Transform player = GameObject.Find ("PlayerCore").transform;
			float defaultx = transform.eulerAngles.x;
			transform.LookAt(player);
			transform.eulerAngles = new Vector3 (defaultx, transform.eulerAngles.y, transform.eulerAngles.z);
		}

		if (Time.time > nextBurst)
		{
			if (Time.time > nextFire && fireable && !broken)
			{
				nextFire = Time.time + fireRate;
				for (int i = 0; i < gameObject.transform.childCount; i++)
				{
					shotSpawn = new Vector3(gameObject.transform.GetChild(i).transform.position.x, gameObject.transform.GetChild(i).transform.position.y, gameObject.transform.GetChild(i).transform.position.z);
					GameObject spawnedShot = Instantiate (
						shot,
						shotSpawn,
						gameObject.transform.GetChild(i).transform.rotation); // Fire
					spawnedShot.transform.parent = GameObject.Find("Action").transform;
					GetComponentInParent<AudioSource> ().Play ();
				}
			}
			if (Time.time > timeStored + timeFiring)
				nextBurst = timePaused + Time.time;
		}
		else timeStored = Time.time;
	}
}
