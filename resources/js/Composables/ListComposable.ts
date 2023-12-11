export const useInitListItem = (item: any): IListItem => {
  let dataItem = { ...item }
  dataItem.chamfer = useJsonParser(item.chamfer)
  dataItem.gazor_hinge = useJsonParser(item.gazor_hinge)
  dataItem.pvc = useJsonParser(item.pvc)
  dataItem.groove = useJsonParser(item.groove)
  dataItem.dimensions = useJsonParser(item.dimensions)
  return dataItem
}

export const useInitListCase = (item: any): IListCaseItem => {
  let dataItem = { ...item }
  dataItem['pvc'] = useJsonParser(item.pvc)
  dataItem['stock'] = useJsonParser(item.stock)
  return dataItem
}
