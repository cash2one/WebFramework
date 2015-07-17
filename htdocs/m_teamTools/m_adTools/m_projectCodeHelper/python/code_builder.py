#!/usr/bin/python

import re

tab = " " * 4
interpretor_str = "#!/usr/bin/python"

def get_class_name(node_name):
    fields = re.split("-", node_name)
    for idx, field in enumerate(fields):
        fields[idx] = field.lower()
        fields[idx] = field[0].upper() + field[1:]
    return "".join(fields) + "Node"


def build_node_type_file():
    file_name = "NodeType.py"
    node_name_list = ("beans", "import", "bean", "context", "property", "ref", "props", "prop", "list", "value", "constructor-arg", "map", "entry")

    lines = [interpretor_str, "", "class Node:", "%spass" % tab]
    for node_name in node_name_list:
        lines.append("")
        lines.append("")
        class_name = get_class_name(node_name)
        lines.append("class %s(Node):" % class_name)
        lines.append("%sdef __init__(self):" % tab)

    lines.append("")
    lines.append("")
    lines.append("class NodeFactory:")
    lines.append("%sdef get_node(node_name):" % tab)
    for idx, node_name in enumerate(node_name_list):
        if idx == 0:
            lines.append("%sif node_name == '%s':" % (tab * 2, node_name))
        else:
            lines.append("%selif node_name == '%s':" % (tab * 2, node_name))
        class_name = get_class_name(node_name)
        lines.append("%sreturn %s()" % (tab * 3, class_name))

    lines.append("%selse:" % (tab * 2))
    lines.append("%sraise Exception('Invalid node name(%s)')" % (tab * 3, node_name))
        

    """
    # comment file update code in case of update file accidently
    handle = open(file_name, "w")
    for line in lines:
        handle.write(line + "\n")
    handle.close()
    """

build_node_type_file()
