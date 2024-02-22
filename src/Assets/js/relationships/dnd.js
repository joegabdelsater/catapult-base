
function allowDrop(event) {
    event.preventDefault();
}

function drag(event, modelName, singleModelName, pluralModelName) {
    event.dataTransfer.setData("origin", modelName);
    event.dataTransfer.setData("singleModelName", singleModelName);
    event.dataTransfer.setData("pluralModelName", pluralModelName);
}

function getMethodNameInput(data) {

    const { relationship, originModelNames, key } = data;

    var name = getMethodName(relationship, originModelNames);
    return `<input type="text" class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 " placeholder="functionName" name="r[${key}][relationship_method_name]" required value="${name}"/> `
}

function getMethodName(relationship, modelNames) {
    if(relationship === 'polymorphic_morph_to') return `${modelNames.single}able`;
    const pluralMethodNameRelationships = ['one_to_many', 'many_to_many'];
    return pluralMethodNameRelationships.includes(relationship) ? `${modelNames.plural}` : `${modelNames.single}`;
}

function getMethodBlock(data) {
    const { relationship, relationshipMethod, destinationModel, originModel, key } = data
    const tab = '&nbsp;&nbsp;&nbsp;&nbsp;';

    var parameters = ' ';

    if (relationship !== 'polymorphic_morph_to') {
        parameters = `${originModel.replace('::class', '')}<span class="text-sky-500">::class</span> </p>
        ${getMethodRelationshipInputs(relationship, key, data)}`;
    }

    return `<div class="p-4">
    <div class="flex flex-row flex-start items-center">
      <p class="text-sm font-bold mr-2">public function</p>
        ${getMethodNameInput(data)}   
      <p class="text-sm font-bold">( ) {</p> 
   </div>

   <div class="flex flex-row flex-start items-center">
    ${tab}
    <p class="text-sm font-bold"><span class="text-sky-500">return </span><span class="text-orange-600">$this->${relationshipMethod}</span>(${parameters});</p>
    </div>
    <p>}</p>  
      <input type="hidden" name="r[${key}][model]" value="${destinationModel}"/>
      <input type="hidden" name="r[${key}][relationship_model]" value="${originModel}"/>
      <input type="hidden" name="r[${key}][relationship]" value="${relationship}"/>
      <input type="hidden" name="r[${key}][relationship_method]" value="${relationshipMethod}"/>
      
</div>
<div class="delete-btn bg-orange-600 rounded-r-md flex items-center justify-center h-full w-full cursor-pointer">${deleteComponent}</div>`;
}

function getMethodRelationshipInputs(relationship, key, data) {
    var inputs = ``;

    relationshipMethodInputs[relationship].forEach(input => {
        if(relationship === 'polymorphic_morph_one' || relationship === 'polymorphic_morph_many') {
            inputs += `<span>,</span><input type="text" class="focused block mx-1 font-bold px-2 w-40 text-sm text-white rounded bg-gray-700  border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40" placeholder="${input}" name="r[${key}][${input}]" value="${data.originModelNames.single}able"/>`
        } else {
            inputs += `<span>,</span><input type="text" class="focused block mx-1 font-bold px-2 w-40 text-sm text-white rounded bg-gray-700  border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40" placeholder="${input}" name="r[${key}][${input}]"/>`
        }     
    });

    return inputs;
}

function createHtmlElements(data) {
    const { destinationModel, relationship, relationshipMethod, originModel, originModelNames, key } = data;
    var element = document.querySelector(`.js-${destinationModel}-${relationship}`);

    const newClass = document.createElement('div');

    newClass.innerHTML = getMethodBlock(data);
    newClass.className = 'bg-white  rounded-md mb-2 grid grid-cols-[26fr_1fr]';
    newClass.setAttribute('data-origin', originModel);

    newClass.querySelector('.delete-btn').addEventListener('click', function () {
        if (confirm('Are you sure you want to remove this relationship?')) {
            this.parentNode.remove();
        }
    });

    element.appendChild(newClass);
}
function drop(event, relationship, destinationModel, relationshipMethod) {
    event.preventDefault();

    const originModel = event.dataTransfer.getData("origin");

    const originModelNames = {
        single: event.dataTransfer.getData("singleModelName"),
        plural: event.dataTransfer.getData("pluralModelName")
    }

    const key = `${destinationModel}-${relationshipMethod}-${originModel}`;

    createHtmlElements({ destinationModel, relationship, relationshipMethod, originModel, originModelNames, key });
}