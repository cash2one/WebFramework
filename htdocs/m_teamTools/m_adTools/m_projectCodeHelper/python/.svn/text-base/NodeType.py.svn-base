#!/usr/bin/python
#encoding: utf-8

import xml.dom.minidom

class Node:
    def read(self):
        self.attr_dict = {}
        for key, val in self.xml_node.attributes.items():
            self.attr_dict[key] = val

        for xml_node2 in self.xml_node.childNodes:
            if xml_node2.nodeType != 1: continue

            node_name = xml_node2.nodeName
            new_node = NodeFactory.build_node(node_name, xml_node2)
            self._read(node_name, new_node)

    def _read(self, node_name, new_node):
        pass

    def output(self):
        # print attrs
        attr_list = ["[%s]" % self.xml_node.nodeName]
        for key, val in self.attr_dict.items():
            attr_list.append("%s=%s" % (key, val))
        print " ".join(attr_list)

        self._output()

    def _output(self):
        pass

    def check_None(self, value):
        if value != None:
            raise Exception("Value of attr should be None")

    def __getitem__(self, key):
        return self.attr_dict.get(key, None)

    def set_xml_file(self, xml_file):
        self.xml_file = xml_file
        return self


class BeansNode(Node):
    def __init__(self, xml_node):
        self.xml_file = None

        self.xml_node = xml_node
        self.context = None
        self.import_list = []
        self.bean_list = []
        self.sec_http = None
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "context:property-placeholder":
            self.check_None(self.context)
            self.context = new_node
        elif node_name == "import":
            self.import_list.append(new_node)
        elif node_name == "bean":
            self.bean_list.append(new_node)
        elif node_name[:4] == "sec:":
            pass
        elif node_name[:3] == "tx:":
            pass
        elif node_name[:4] == "aop:":
            pass
        elif node_name[:6] == "jaxws:":
            pass
        else:
            raise Exception("Invalid node name(%s) for Beans" % node_name)

    def _output(self):
        for bean_node in self.bean_list:
            bean_node.output()


class ImportNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.read()


class BeanNode(Node):
    def __init__(self, xml_node):
        self.xml_file = None
        self.xml_node = xml_node
        self.property_list = []
        self.constructor_arg_list = []
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "property":
            self.property_list.append(new_node)
        elif node_name == "constructor-arg":
            self.constructor_arg_list.append(new_node)
        elif node_name[:4] == "sec:":
            pass
        else:
            raise Exception("Invalid node name(%s) for Bean" % node_name)

    def _output(self):
        for property in self.property_list:
            property.output()

        if self.constructor_arg:
            self.constructor_arg.output()
        


class ContextPPNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.read()


class PropertyNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.bean_list = []
        self.props = None
        self.ref = None
        self.list = None
        self.value = None
        self.map = None
        self.set = None
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "bean":
            self.bean_list.append(new_node)
        elif node_name == "props":
            self.check_None(self.props)
            self.props = new_node
        elif node_name == "ref":
            self.check_None(self.ref)
            self.ref = new_node
        elif node_name == "list":
            self.check_None(self.list)
            self.list = new_node
        elif node_name == "value":
            self.check_None(self.value)
            self.value = new_node
        elif node_name == "map":
            self.check_None(self.map)
            self.map = new_node
        elif node_name == "set":
            self.check_None(self.set)
            self.set = new_node
        else:
            raise Exception("Invalid node name(%s) for Property" % node_name)

    def _output(self):
        for bean in self.bean_list:
            bean.output()

        if self.props:
            self.props.output()

        if self.ref:
            self.ref.output() 

        if self.list:
            self.list.output()

        if self.value:
            self.value.output()

        if self.map:
            self.map.output()


class RefNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.read()


class PropsNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.prop_list = []
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "prop":
            self.prop_list.append(new_node)
        else:
            raise Exception("Invalid node name(%s) for Props" % node_name)

    def _output(self):
        for prop in self.prop_list:
            prop.output()


class PropNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.innerValue = xml_node.firstChild.nodeValue
        self.read()

    def _output(self):
        print "inner value: %s" % self.innerValue


class ListNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.value_list = []
        self.ref_list = []
        self.bean_list = []
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "value":
            self.value_list.append(new_node)
        elif node_name == "ref":
            self.ref_list.append(new_node)
        elif node_name == "bean":
            self.bean_list.append(new_node)
        else:
            raise Exception("Invalid node name(%s) for List" % node_name)

    def _output(self):
        for value in self.value_list:
            value.output() 

        for ref in self.ref_list:
            ref.output()


class ValueNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        if len(xml_node.childNodes) == 0:
            self.innerValue = ""
        else:
            self.innerValue = xml_node.firstChild.nodeValue
        self.read()

    def _output(self):
        print "inner value:" + self.innerValue


class ConstructorArgNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.value_list = []
        self.list = None
        self.ref = None
        self.bean = None
        self.map = None
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "value":
            self.value_list.append(new_node)
        elif node_name == "list":
            self.check_None(self.list)
            self.list = new_node
        elif node_name == "ref":
            self.check_None(self.ref)
            self.ref = new_node
        elif node_name == "bean":
            self.check_None(self.bean)
            self.bean = new_node
        elif node_name == "map":
            self.check_None(self.map)
            self.map = new_node
        else:
            raise Exception("Invalid node name(%s) for ConstructorArg" % node_name)

    def _output(self):
        for value in self.value_list:
            value.output()


class MapNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.entry_list = []
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "entry":
            self.entry_list.append(new_node)
        else:
            raise Exception("Invalid node name(%s) for Map" % node_name)

    def _output(self):
        for entry in self.entry_list:
            entry.output()


class EntryNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.key = None
        self.ref = None
        self.bean = None
        self.value = None
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "key":
            self.check_None(self.key)
            self.key = new_node
        elif node_name == "ref":
            self.check_None(self.ref)
            self.ref = new_node
        elif node_name == "bean":
            self.check_None(self.bean)
            self.bean = new_node
        elif node_name == "value":
            self.check_None(self.value)
            self.value = new_node
        else:
            raise Exception("Invalid node name(%s) for entry" % node_name)

    def _output(self):
        if self.key:
            self.key.output()
        if self.ref:
            self.ref.output()
        if self.bean:
            self.bean.output()


class KeyNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.value = None
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "value":
            self.check_None(self.value)
            self.value = new_node
        else:
            raise Exception("Invalid node name(%s) for Key" % node_name)

    def _output(self):
        if self.value:
            self.value.output()

class SetNode(Node):
    def __init__(self, xml_node):
        self.xml_node = xml_node
        self.value_list = []
        self.read()

    def _read(self, node_name, new_node):
        if node_name == "value":
            self.value_list.append(new_node)
        else:
            raise Exception("Invalid node name(%s) for Set" % node_name)

    def _output(self):
        for value in self.value_list:
            value.output()


class NodeFactory:
    @staticmethod
    def build_node(node_name, xml_node):
        if node_name == 'beans':
            return BeansNode(xml_node)
        elif node_name == 'import':
            return ImportNode(xml_node)
        elif node_name == 'bean':
            return BeanNode(xml_node)
        elif node_name == 'context:property-placeholder':
            return ContextPPNode(xml_node)
        elif node_name == 'property':
            return PropertyNode(xml_node)
        elif node_name == 'ref':
            return RefNode(xml_node)
        elif node_name == 'props':
            return PropsNode(xml_node)
        elif node_name == 'prop':
            return PropNode(xml_node)
        elif node_name == 'list':
            return ListNode(xml_node)
        elif node_name == 'value':
            return ValueNode(xml_node)
        elif node_name == 'constructor-arg':
            return ConstructorArgNode(xml_node)
        elif node_name == 'map':
            return MapNode(xml_node)
        elif node_name == 'entry':
            return EntryNode(xml_node)
        elif node_name == 'key':
            return KeyNode(xml_node)
        elif node_name == 'set':
            return SetNode(xml_node)
        elif node_name[:4] == "sec:":
            pass
        elif node_name[:3] == "tx:":
            pass
        elif node_name[:4] == "aop:":
            pass
        elif node_name[:6] == "jaxws:":
            pass
        else:
            raise Exception('Invalid node name(%s)' % node_name)
