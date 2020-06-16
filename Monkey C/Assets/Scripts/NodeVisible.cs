using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class NodeVisible : MonoBehaviour {
	public bool nodesVisible;
	// Use this for initialization
	void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {
		foreach (MeshRenderer mesh in GetComponentsInChildren<MeshRenderer>())
			if (nodesVisible)
				mesh.enabled = true;
			else
				mesh.enabled = false;
	}
}
