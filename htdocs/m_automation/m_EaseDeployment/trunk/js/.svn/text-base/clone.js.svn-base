function Clone(original)
{
    if(typeof(original) =="object") 
    {
        if (original.length != undefined)
        {
            return CloneArray(original);
        }

        return CloneObject(original);
    }

    return original;
}

function CloneObject(original)
{
    var newObj =new Object();

    for(var ele in original)
    {
        if(typeof(original[ele]) =="object") 
        {
            if(original[ele] == null){
                newObj[ele] = null;
            }
            else if (original[ele].length != undefined)
            {
                 newObj[ele] = CloneArray(original[ele]);
            }
            else
            {
                newObj[ele] = CloneObject(original[ele]);
            }
        }
        else
        {
            newObj[ele] = original[ele];
        }
    }

    return newObj;
}

function CloneArray(original)
{
    var newArray =new Array();

    for (var i =0; i < original.length; i++)
    {
        if(typeof(original[i]) =="object") 
        {
            if (original[i].length != undefined)
            {
                newArray[i] = CloneArray(original[i]);
            }
            else
            {
                newArray[i] = CloneObject(original[i]);
            }
        }
        else
        {
            newArray[i] = original[i];
        }
    }

    return newArray;
}
