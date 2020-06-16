using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Waypoints : MonoBehaviour
{

	public Transform[] waypointList;
	public float speed;

	private int currentWayPoint = 0; 
	private float rotateSpeed = 0;
	private float movementSpeed, waypointDist;
	private Quaternion targetRotation, startRotation;

	void Start()
	{
		startRotation = transform.rotation;

		if (waypointList.Length == 0)
			return;
		
		targetRotation = Quaternion.Euler (0, (waypointList[currentWayPoint].GetComponent<WaypointData>().rotate), 0);

	}

	void FixedUpdate ()
	{
		if (waypointList.Length == 0)
			return;

		WaypointData waypointData = waypointList [currentWayPoint].gameObject.GetComponent<WaypointData> ();
		waypointDist = Vector3.Distance (waypointList [currentWayPoint == 0 ? waypointList.Length - 1: currentWayPoint - 1].position, 
			waypointList [currentWayPoint].position);

		movementSpeed = waypointData.speed * speed * Time.deltaTime;
		rotateSpeed = (movementSpeed * waypointData.rotate / waypointDist);
		//Debug.Log ("dist = " + waypointDist);

		if (transform.position != waypointList [currentWayPoint].position)
		{
			GetComponent<Rigidbody>().MoveRotation(Quaternion.Euler(0, rotateSpeed, 0) * transform.rotation);
			Vector3 pos = Vector3.MoveTowards (transform.position, waypointList [currentWayPoint].position, movementSpeed);
			GetComponent<Rigidbody> ().MovePosition (pos);
		} 
		else
		{
			GetComponent<Rigidbody>().rotation = targetRotation * startRotation;
			startRotation = transform.rotation;
			currentWayPoint = (currentWayPoint + 1) % waypointList.Length;
			targetRotation = Quaternion.Euler (0, (waypointList[currentWayPoint].GetComponent<WaypointData>().rotate), 0);

			startRotation = GetComponent<Rigidbody>().rotation;
		}

	}
}
