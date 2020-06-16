using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SpangleBehavior : MonoBehaviour {

	public GameObject[] guns;
	private GameObject[] patterns;
	private int currentPattern = 0;
	private bool waiting = false, charging = false;
	private Vector3 pos;
	// Use this for initialization
	void Start ()
	{
		GameObject.Find ("Action Camera").GetComponent<SceneLoader> ().bossTime = Time.time;
		patterns = new GameObject[] {GameObject.Find("Spangle Entry"), GameObject.Find("Strafe"), GameObject.Find("Charge")};
	}
	
	// Update is called once per frame
	void FixedUpdate ()
	{
		if (transform.position == GetComponent<Waypoints> ().waypointList [GetComponent<Waypoints>().waypointList.Length - 1].transform.position)
			GameObject.Find ("Action").GetComponent<GameController> ().boss = true;

		if (GameObject.Find ("Action").GetComponent<GameController> ().boss)
		{
			GetComponent<Waypoints> ().waypointList = new Transform[patterns [currentPattern].transform.childCount];
			for (int i = 0; i < patterns [currentPattern].transform.childCount; i++)
				GetComponent<Waypoints> ().waypointList [i] = patterns [currentPattern].transform.GetChild (i).transform;

			if (currentPattern == 0)
			{

				for (int i = 0; i < guns.Length; i++)
					if (guns[i].gameObject.GetComponent<AimShoot>() != null)
						guns [i].gameObject.GetComponent<AimShoot> ().fireable = false;
				
				if (transform.position == GetComponent<Waypoints> ().waypointList [GetComponent<Waypoints> ().waypointList.Length - 1].transform.position)
				{
					patterns [currentPattern].transform.GetChild (0).GetComponent<WaypointData> ().speed = 0.0f;
					for (int i = 0; i < guns.Length; i++)
						guns [i].GetComponent<AimShoot> ().fireable = true;
					Debug.Log (waiting);
					if (!waiting)
					{
						waiting = true;
						StartCoroutine(Wait (5f));
					}
				}
			}
			else if (currentPattern == 2)
			{
				if (transform.position.z == GetComponent<Waypoints> ().waypointList [0].transform.position.z)
					charging = true;
					
				
				if (transform.position == GetComponent<Waypoints> ().waypointList [GetComponent<Waypoints> ().waypointList.Length - 1].transform.position)
				{
					charging = false;
					patterns [2].transform.localPosition = new Vector3 (0.0f, 0.0f, 0.0f);
					currentPattern = 0;
					patterns [currentPattern].transform.GetChild (0).GetComponent<WaypointData> ().speed = 0.1f;
					transform.position = patterns [currentPattern].transform.GetChild(0).position;
				}
			}


		}

		if (charging)
			for (int i = 0; i < guns.Length; i++)
				guns [i].GetComponent<AimShoot> ().fireable = false;
		else if (!charging && currentPattern != 0)
			for (int i = 0; i < guns.Length; i++)
				guns [i].GetComponent<AimShoot> ().fireable = true;
	}

	IEnumerator Wait(float time)
	{
		waiting = true;
		yield return new WaitForSeconds (time);
		if (GetComponent<Health> ().health < GetComponent<Health> ().maxHealth / 3)
			currentPattern = 1;
		else
			currentPattern = 2;
		waiting = false;
	}
}
