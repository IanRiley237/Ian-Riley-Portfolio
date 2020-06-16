using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class StarBroBehavior : MonoBehaviour {

	private GameObject[] patterns;
	private bool firstFrameOfSecondPhase = true;

	// Use this for initialization
	void Start () {
		if (transform.localPosition.x < 0)
		{
			// Left StarBro
			patterns = new GameObject[] {GameObject.Find("Shoot Right from Left"), GameObject.Find("Center")};
		} else
		{
			// Right StarBro
			patterns = new GameObject[] {GameObject.Find("Shoot Left from Right"), GameObject.Find("Center")};
		}
	}
	
	// Update is called once per frame
	void FixedUpdate () {
		if (transform.position == GetComponent<Waypoints> ().waypointList [GetComponent<Waypoints>().waypointList.Length - 1].transform.position)
			GameObject.Find ("Action").GetComponent<GameController> ().enabled = false;

		if (!GameObject.Find ("Action").GetComponent<GameController> ().enabled && GameObject.FindGameObjectsWithTag ("StarBro").Length != 1)
		{
			GetComponent<Waypoints> ().waypointList = new Transform[patterns [0].transform.childCount];
			for (int i = 0; i < patterns [0].transform.childCount; i++)
				GetComponent<Waypoints> ().waypointList [i] = patterns [0].transform.GetChild (i).transform;
		} else if (GameObject.FindGameObjectsWithTag ("StarBro").Length == 1 && GetComponent<Health>().health < GetComponent<Health>().maxHealth)
		{/*
			GetComponent<Waypoints> ().waypointList = new Transform[patterns [1].transform.childCount];
			for (int i = 0; i < patterns [1].transform.childCount; i++)
				GetComponent<Waypoints> ().waypointList [i] = patterns [1].transform.GetChild (i).transform;
*/
			GetComponent<Waypoints> ().enabled = false;
			GetComponent<Behavior> ().rotateSpeed = 1.2f;
			GetComponent<Rigidbody> ().MovePosition (Vector3.MoveTowards(transform.position, patterns[1].transform.GetChild(0).position, 0.2f));

			GameObject[] guns = GameObject.FindGameObjectsWithTag ("EnemyGun");

			for (int i = 0; i < guns.Length; i++)
			{
				if (firstFrameOfSecondPhase)
				{
					//guns [i].GetComponent<AimShoot> ().nextFire = 0f;
					firstFrameOfSecondPhase = false;
				}
				guns [i].GetComponent<AimShoot> ().fireable = true;
				guns [i].GetComponent<AimShoot> ().fireRate = 0.3f;
				guns [i].GetComponent<AimShoot> ().timeFiring = 1f;
				guns [i].GetComponent<AimShoot> ().timePaused = 0f;
				guns [i].GetComponent<AimShoot> ().aimable = false;
			}
		}

	}
}
